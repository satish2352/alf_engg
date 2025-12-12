<?php
namespace App\Http\Controllers\Superadm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Superadm\PlantMasterService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rule;
use Exception;
use App\Exports\PlantsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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
				// 'regex:/^[a-zA-Z0-9\s]+$/',
				// 'regex:/^[a-zA-Z0-9\s\-\_\&\.]+$/',
				'regex:/^.+$/',
				Rule::unique('plant_masters', 'plant_name')->where(function ($query) {
					return $query->where('is_deleted', 0);
				}),
			],
    // 'address' => 'required',
    'city' => [
            'required',
            'regex:/^[a-zA-Z\s]+$/', 
        ],
	'week_off' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
    // 'plant_short_name' => 'nullable|max:255', 
], [
    'plant_code.required' => 'Enter plant code',
    'plant_code.unique' => 'This plant code already exists.',
    'plant_code.max' => 'Plant code must not exceed 255 characters.',
    'plant_name.required' => 'Enter plant name',
    'plant_name.max' => 'Plant name must not exceed 255 characters.',
     'plant_name.regex' => 'Plant Name can contain any characters.',
    // 'address.required' => 'Enter address for plant',
   'city.required' => 'Enter city for plant',
   'week_off' => 'Please select week off',
        'city.regex' => 'City name must contain only letters and spaces.',

    // 'plant_short_name.required' => 'Enter plant short name',
]);


		try {
			// $this->service->save($req);
			// return redirect()->route('plantmaster.list')->with('success', 'Plant details added successfully.');
		$createdBy = session('employee_user_name'); // or whatever session key
		$data = array_merge($req->all(), ['created_by' => $createdBy]);

		$result = $this->service->save($data);

		if (!$result) {
			return redirect()->back()->withInput()->with('error', 'Failed to insert plant details.');
		}

		return redirect()->route('plantmaster.list')->with('success', 'Plant details added successfully.');

		} catch (Exception $e) {
			return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
		}

	}

	// public function sendApi(Request $request)
	// {
	// 	$request->validate([
	// 		'id' => 'required',
	// 		'projects' => 'required|array|min:1'
	// 	]);

	// 	try {

	// 		// Fetch plant
	// 		$plant = \DB::table('plant_masters')
	// 			->where('id', $request->id)
	// 			->where('is_deleted', 0)
	// 			->first();

	// 		if (!$plant) {
	// 			return response()->json([
	// 				'status' => false,
	// 				'message' => "Plant not found!"
	// 			]);
	// 		}

	// 		// Fetch selected projects
	// 		$projects = \DB::table('projects')
	// 			->whereIn('id', $request->projects)
	// 			->where('is_active', 1)
	// 			->where('is_deleted', 0)
	// 			->get();

	// 		$responses = [];
	// 		$allSuccess = true;

	// 		foreach ($projects as $proj) {

	// 			// Extract project folder name
	// 			preg_match('#https?://[^/]+/([^/]+)/#', $proj->project_url, $m);
	// 			$projectName = $m[1] ?? null;

	// 			if (!$projectName) {
	// 				$allSuccess = false;
	// 				continue;
	// 			}

	// 			$payload = [
	// 				"plant_code" => $plant->plant_code,
	// 				"plant_name" => $plant->plant_name,
	// 				"short_name" => $plant->plant_short_name,
	// 				"address"    => $plant->address ?: 'abc',
	// 				"city"       => $plant->city ?: 'abc',
	// 				"week_off"   => $plant->week_off
	// 			];

	// 			$apiUrl = "https://alfitworld.com/{$projectName}/CommonController/addEditPlantApi";

	// 			$apiResponse = \Http::post($apiUrl, $payload);

	// 			$success = $apiResponse->successful();
	// 			if (!$success) $allSuccess = false;

	// 			$responses[] = [
	// 				"project" => $proj->project_name,
	// 				"payload_sent" => $payload,
	// 				"api_url" => $apiUrl,
	// 				"status" => $success,
	// 				"response" => $apiResponse->body()
	// 			];
	// 		}

	// 		// UPDATE ONLY IF ALL PROJECT API CALLS SUCCESSFUL
	// 		if ($allSuccess) {
	// 			\DB::table('plant_masters')
	// 				->where('id', $request->id)
	// 				->update([
	// 					'send_api' => 1,
	// 					'send_api_project_id' => implode(",", $request->projects)
	// 				]);

	// 			return response()->json([
	// 				'status' => true,
	// 				'message' => "Plant data sent successfully!",
	// 				'data' => $responses
	// 			]);
	// 		}

	// 		// If any project API FAIL
	// 		return response()->json([
	// 			'status' => false,
	// 			'message' => "Some projects failed to receive plant data.",
	// 			'data' => $responses
	// 		]);

	// 	} catch (\Exception $e) {

	// 		return response()->json([
	// 			'status' => false,
	// 			'message' => $e->getMessage()
	// 		]);
	// 	}
	// }

	public function sendApi(Request $request)
{
    $request->validate([
        'id' => 'required',
        'projects' => 'required|array|min:1'
    ]);

    try {
        // Fetch plant
        $plant = \DB::table('plant_masters')
            ->where('id', $request->id)
            ->where('is_deleted', 0)
            ->first();

        if (!$plant) {
            return response()->json([
                'status' => false,
                'message' => "Plant not found!"
            ]);
        }

        // NEW selected projects from UI
        $newProjects = $request->projects;

        // OLD saved projects
        $oldProjects = explode(",", $plant->send_api_project_id ?? "");

        // =============================
        // 🔥 Step 1: Identify Removed Projects
        // =============================
        $removedProjects = array_diff($oldProjects, $newProjects);

        if (!empty($removedProjects)) {

            $removed = \DB::table('projects')
                ->whereIn('id', $removedProjects)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->get();

            foreach ($removed as $proj) {

                // Extract folder name from project URL
                preg_match('#https?://[^/]+/([^/]+)/#', $proj->project_url, $m);
                $projectName = $m[1] ?? null;

                if (!$projectName) continue;

                $payload = [
                    "plant_code" => $plant->plant_code,
                    "status"     => 0 // 🔥 Mark plant as disabled for this project
                ];

                $url = "https://alfitworld.com/{$projectName}/CommonController/changePlantStatus";

                \Http::post($url, $payload);
            }
        }


        // =============================
        // 🔥 Step 2: SEND DATA for Selected Projects
        // =============================

        $projects = \DB::table('projects')
            ->whereIn('id', $newProjects)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->get();

        $responses = [];
        $allSuccess = true;

        foreach ($projects as $proj) {

            // Extract project folder name
            preg_match('#https?://[^/]+/([^/]+)/#', $proj->project_url, $m);
            $projectName = $m[1] ?? null;

            if (!$projectName) {
                $allSuccess = false;
                continue;
            }

            $payload = [
                "plant_code" => $plant->plant_code,
                "plant_name" => $plant->plant_name,
                "short_name" => $plant->plant_short_name,
                "address"    => $plant->address ?: 'abc',
                "city"       => $plant->city ?: 'abc',
                "week_off"   => $plant->week_off
            ];

            $apiUrl = "https://alfitworld.com/{$projectName}/CommonController/addEditPlantApi";

            $apiResponse = \Http::post($apiUrl, $payload);

            $success = $apiResponse->successful();
            if (!$success) $allSuccess = false;

            $responses[] = [
                "project" => $proj->project_name,
                "payload" => $payload,
                "api_url" => $apiUrl,
                "status" => $success,
                "response" => $apiResponse->body()
            ];
        }

        // =============================
        // 🔥 Step 3: UPDATE DATABASE if everything successful
        // =============================
        if ($allSuccess) {

            \DB::table('plant_masters')
                ->where('id', $request->id)
                ->update([
                    'send_api' => 1,
                    'send_api_project_id' => implode(",", $newProjects)
                ]);

            return response()->json([
                'status' => true,
                'message' => "Plant data sent successfully!",
                'data' => $responses
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => "Some projects failed to receive plant data.",
            'data' => $responses
        ]);


    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ]);
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
			'week_off' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
			// 'address' => 'required',
			 'city' => [
            'required',
            'regex:/^[a-zA-Z\s]+$/', // only letters and spaces
        ],
			// 'plant_short_name' => 'required',
			'id' => 'required',
			'is_active' => 'required'

		], [
			'plant_code.required' => 'Enter plant code',
			'plant_code.unique' => 'This plant code already exists.',
			'id.required' => 'ID required',
			'plant_name.required' => 'Enter plant name',
			// 'address.required' => 'Enter address for plant',
			'city.required' => 'Enter city for plant',
			'city.regex' => 'City name must contain only letters and spaces.',
			'week_off' => 'Please select week off',
			// 'plant_short_name.required' => 'Enter plant short name',
			'is_active.required' => 'Select active or inactive required'
		]);

		try {
			$req->merge(['send_api' => 0]);

			$this->service->update($req);
			return redirect()->route('plantmaster.list')->with('success', 'Plant details updated successfully.');
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
	// 		return redirect()->route('plantmaster.list')->with('success', 'Plant details deleted successfully.');
	// 	} catch (Exception $e) {
	// 		return redirect()->back()->with('error', 'Failed to delete plant: ' . $e->getMessage());
	// 	}
	// }

	// public function delete(Request $req)
	// {
	// 	try {
	// 		$req->validate([
	// 			'id' => 'required',
	// 		], [
	// 			'id.required' => 'ID required'
	// 		]);

	// 		$id = base64_decode($req->id);

	// 		// ✅ Get the plant
	// 		$plant = \DB::table('plant_masters')
	// 			->where('id', $id)
	// 			->where('is_deleted', 0)
	// 			->first();

	// 		if (!$plant) {
	// 			return redirect()->route('plantmaster.list')
	// 				->with('error', 'Plant not found or already deleted.');
	// 		}

	// 		// ✅ Check if employees exist for this plant
	// 		$employeeExists = \DB::table('employee_plant_assignments')
	// 			->where('plant_id', $id)
	// 			->where('is_deleted', 0)
	// 			->exists();

	// 		if ($employeeExists) {
	// 			return redirect()->route('plantmaster.list')
	// 				->with('error', "Cannot delete the plant '{$plant->plant_name}' because employees are assigned to it.");
	// 		}

	// 		// ✅ Check if projects exist for this plant
	// 		$projectExists = \DB::table('projects')
	// 			->where('plant_id', $id)
	// 			->where('is_deleted', 0)
	// 			->exists();

	// 		if ($projectExists) {
	// 			return redirect()->route('plantmaster.list')
	// 				->with('error', "Cannot delete the plant '{$plant->plant_name}' because projects are assigned to it.");
	// 		}

	// 		// ✅ Check if departments exist for this plant
	// 		$departmentExists = \DB::table('departments')
	// 			->where('plant_id', $id)
	// 			->where('is_deleted', 0)
	// 			->exists();

	// 		if ($departmentExists) {
	// 			return redirect()->route('plantmaster.list')
	// 				->with('error', "Cannot delete the plant '{$plant->plant_name}' because departments are assigned to it.");
	// 		}

	// 		// ✅ If no dependencies, soft delete
	// 		$this->service->delete($req);

	// 		return redirect()->route('plantmaster.list')
	// 			->with('success', "Plant '{$plant->plant_name}' deleted successfully.");

	// 	} catch (Exception $e) {
	// 		return redirect()->back()
	// 			->with('error', 'Failed to delete plant: ' . $e->getMessage());
	// 	}
	// }

	public function delete(Request $req)
	{
		try {
			$id = base64_decode($req->id);

			$plant = \DB::table('plant_masters')
				->where('id', $id)
				->where('is_deleted', 0)
				->first();

			if (!$plant) {
				return redirect()->route('plantmaster.list')
					->with('error', 'Plant not found or already deleted.');
			}

			// Check dependencies (employees, projects, departments)
			$employeeExists = \DB::table('employee_plant_assignments')
				->where('plant_id', $id)->where('is_deleted', 0)->exists();

			$projectExists = \DB::table('projects')
				->where('plant_id', $id)->where('is_deleted', 0)->exists();

			$departmentExists = \DB::table('departments')
				->where('plant_id', $id)->where('is_deleted', 0)->exists();

			if ($employeeExists || $projectExists || $departmentExists) {
				return redirect()->route('plantmaster.list')->with(
					'error',
					"Cannot delete plant '{$plant->plant_name}' because related records exist."
				);
			}

			// 🔹 Call delete API for all previously sent projects
			$projectIds = explode(",", $plant->send_api_project_id ?? "");

			$projects = \DB::table('projects')
				->whereIn('id', $projectIds)
				->where('is_active', 1)
				->get();

			foreach ($projects as $proj) {

				preg_match('#https?://[^/]+/([^/]+)/#', $proj->project_url, $m);
				$projectName = $m[1] ?? null;

				if (!$projectName) continue;

				$payload = [
					"plant_code" => $plant->plant_code
				];

				$url = "https://alfitworld.com/{$projectName}/CommonController/deletePlantApi";

				\Http::post($url, $payload); // API Call
			}

			// 🔹 Soft delete in local DB
			\DB::table('plant_masters')->where('id', $id)->update([
				'is_deleted' => 1
			]);

			return redirect()->route('plantmaster.list')
				->with('success', "Plant '{$plant->plant_name}' deleted successfully.");

		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Error deleting plant: ' . $e->getMessage());
		}
	}

	public function validateData(Request $req)
	{

	}

	// public function updateStatus(Request $req)
	// {
	// 	try {
	// 		$this->service->updateStatus($req);
	// 		return redirect()->route('plantmaster.list')->with('success', 'Plant details status updated successfully.');
	// 	} catch (Exception $e) {
	// 		return redirect()->back()->with('error', 'Failed to update status: ' . $e->getMessage());
	// 	}
	// }

// 	public function updateStatus(Request $request)
// {
//     try {
//         $id = base64_decode($request->id);
//         $plant = \DB::table('plant_masters')->where('id', $id)->first();

//         if (!$plant) {
//             return response()->json(['status' => false, 'message' => 'Plant not found'], 404);
//         }

//         $is_active = $request->is_active ? 1 : 0;
//         \DB::table('plant_masters')->where('id', $id)->update(['is_active' => $is_active]);

//         $statusText = $is_active ? 'activated' : 'deactivated';
//         $message = "Plant '{$plant->plant_name}' status {$statusText} successfully";

//         return response()->json(['status' => true, 'message' => $message]);
//     } catch (Exception $e) {
//         return response()->json(['status' => false, 'message' => 'Failed to update status: ' . $e->getMessage()], 500);
//     }
// }

public function updateStatus(Request $request)
{
    try {
        $id = base64_decode($request->id);

        $plant = \DB::table('plant_masters')
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->first();

        if (!$plant) {
            return response()->json([
                'status' => false,
                'message' => 'Plant not found'
            ], 404);
        }

        $is_active = $request->is_active ? 1 : 0;

        // 🔹 Update local DB
        \DB::table('plant_masters')->where('id', $id)->update([
            'is_active' => $is_active
        ]);

        // 🔹 Fetch projects in which plant was sent earlier
        $projectIds = explode(",", $plant->send_api_project_id ?? "");

        $projects = \DB::table('projects')
            ->whereIn('id', $projectIds)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->get();

        $responses = [];

        foreach ($projects as $proj) {

            preg_match('#https?://[^/]+/([^/]+)/#', $proj->project_url, $m);
            $projectName = $m[1] ?? null;

            if(!$projectName) continue;

            $payload = [
                "plant_code" => $plant->plant_code,
                "status"     => $is_active
            ];

            $apiUrl = "https://alfitworld.com/{$projectName}/CommonController/changePlantStatus";

            $apiResponse = \Http::post($apiUrl, $payload);

            $responses[] = [
                "project" => $proj->project_name,
                "api_url" => $apiUrl,
                "sent_payload" => $payload,
                "status" => $apiResponse->successful(),
                "response" => $apiResponse->body()
            ];
        }

        $statusText = $is_active ? 'activated' : 'deactivated';

        return response()->json([
            'status' => true,
            'message' => "Plant '{$plant->plant_name}' has been {$statusText} successfully.",
            'api_logs' => $responses
        ]);

    } catch (Exception $e) {
        return response()->json([
            'status' => false,
            'message' => "Error updating status: " . $e->getMessage()
        ], 500);
    }
}


public function export(Request $request)
{
    $search = $request->search;
    $type = $request->type ?? 'excel'; // default to excel

    $query = \DB::table('plant_masters')->where('is_deleted', 0);

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('plant_code', 'like', "%{$search}%")
              ->orWhere('plant_name', 'like', "%{$search}%")
              ->orWhere('address', 'like', "%{$search}%")
              ->orWhere('city', 'like', "%{$search}%")
              ->orWhere('plant_short_name', 'like', "%{$search}%");
        });
    }

    $plants = $query->get();

    if ($plants->isEmpty()) {
        return redirect()->back()->with('error', 'No data available to export.');
    }

    if ($type == 'excel') {
        $fileName = 'plants_' . date('Y_m_d') . '.xlsx';
        return Excel::download(new PlantsExport($search), $fileName);
    }

	if ($type == 'pdf') {
		$pdf = Pdf::loadView('superadm.plantmaster.pdf', compact('plants'))
				->setPaper('A4', 'landscape'); // <-- Landscape for wide tables
		return $pdf->download('plants_' . date('Y_m_d') . '.pdf');
	}

    return redirect()->back()->with('error', 'Invalid export type.');
}



// public function updateStatus(Request $request)
// {
//     try {
//         $id = base64_decode($request->id);

//         // Fetch the plant
//         $plant = \DB::table('plant_masters')
//             ->where('id', $id)
//             ->where('is_deleted', 0)
//             ->first();

//         if (!$plant) {
//             return response()->json(['status' => false, 'message' => 'Plant not found'], 404);
//         }

//         $is_active = $request->is_active ? 1 : 0;

//         // ✅ If trying to deactivate, check dependencies
//         if ($is_active == 0) {
//             $employeeExists = \DB::table('employees')
//                 ->where('plant_id', $id)
//                 ->where('is_deleted', 0)
//                 ->exists();

//             $projectExists = \DB::table('projects')
//                 ->where('plant_id', $id)
//                 ->where('is_deleted', 0)
//                 ->exists();

//             $departmentExists = \DB::table('departments')
//                 ->where('plant_id', $id)
//                 ->where('is_deleted', 0)
//                 ->exists();

//             if ($employeeExists || $projectExists || $departmentExists) {
//                 $message = "Cannot deactivate the plant '{$plant->plant_name}' because it has assigned ";
//                 $parts = [];
//                 if ($employeeExists) $parts[] = "employees";
//                 if ($projectExists) $parts[] = "projects";
//                 if ($departmentExists) $parts[] = "departments";
//                 $message .= implode(', ', $parts) . ".";
                
//                 return response()->json(['status' => false, 'message' => $message], 400);
//             }
//         }

//         // ✅ Update status
//         \DB::table('plant_masters')->where('id', $id)->update(['is_active' => $is_active]);

//         $statusText = $is_active ? 'activated' : 'deactivated';
//         $message = "Plant '{$plant->plant_name}' status {$statusText} successfully";

//         return response()->json(['status' => true, 'message' => $message]);

//     } catch (Exception $e) {
//         return response()->json(['status' => false, 'message' => 'Failed to update status: ' . $e->getMessage()], 500);
//     }
// }


}
