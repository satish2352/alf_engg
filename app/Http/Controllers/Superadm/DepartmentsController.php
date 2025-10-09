<?php
namespace App\Http\Controllers\Superadm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Superadm\DepartmentsService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rule;
use App\Models\PlantMasters;
use Exception;
use App\Exports\DepartmentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class DepartmentsController extends Controller
{
	protected $service;
	function __construct()
	{
		$this->service = new DepartmentsService();
	}

	public function index()
	{
		try {
			$dataAll = $this->service->list();
			return view('superadm.departments.list', compact('dataAll'));
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}

	public function listajaxlist(Request $req)
	{
		try {
			$department = $this->service->listajaxlist($req);
			return response()->json(['department' => $department]);

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
			return view('superadm.departments.create', compact('plants'));
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}

	public function save(Request $req)
	{

		$req->validate([
			'department_code' => [
				'required',
				Rule::unique('departments', 'department_code')->where(function ($query) {
					return $query->where('is_deleted', 0);
				}),
			],
			'department_name' => [
				'required',
				Rule::unique('departments', 'department_name')->where(function ($query) {
					return $query->where('is_deleted', 0);
				}),
			],
			'plant_id' => 'required',
			// 'department_short_name' => 'required',


		], [
			'department_code.required' => 'Enter deparment code',
			'department_code.unique' => 'This deparment code already exists.',

			'department_name.required' => 'Enter department name ',
			'department_name.unique' => 'This department name already exists.',

			'plant_id.required' => 'Please select plant.',
			// 'department_short_name.required' => 'Department short description required.',
		]);

		try {
			// $this->service->save($req);
			// return redirect()->route('departments.list')->with('success', 'Department added successfully.');
			$createdBy = session('email_id'); // or whatever session key
			$data = array_merge($req->all(), ['created_by' => $createdBy]);

			$result = $this->service->save($data);

			if (!$result) {
				return redirect()->back()->withInput()->with('error', 'Failed to insert department details.');
			}

			return redirect()->route('departments.list')->with('success', 'Department added successfully.');

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
			return view('superadm.departments.edit', compact('data', 'plants', 'encodedId'));
		} catch (Exception $e) {
			return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
		}
	}

	public function update(Request $req)
	{
		$req->validate([
			'department_code' => [
				'required',
				Rule::unique('departments', 'department_code')
					->where(fn($query) => $query->where('is_deleted', 0))
					->ignore($req->id),

			],
			'department_name' => [
				'required',
				Rule::unique('departments', 'department_name')
					->where(fn($query) => $query->where('is_deleted', 0))
					->ignore($req->id),
			],
			'plant_id' => 'required',
			// 'department_short_name' => 'required',
			'id' => 'required',
			'is_active' => 'required'
		], [
			'department_code.required' => 'Enter deparment code',
			'department_code.unique' => 'This deparment code already exists.',

			'department_name.required' => 'Enter department name ',
			'department_name.unique' => 'This department name already exists.',

			'plant_id.required' => 'Please select plant.',
			// 'department_short_name.required' => 'Department short description required.',
			'id.required' => 'ID required',
			'is_active.required' => 'Select active or inactive required'
		]);

		try {
			$this->service->update($req);
			return redirect()->route('departments.list')->with('success', 'Department updated successfully.');
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
			return redirect()->route('departments.list')->with('success', 'Department deleted successfully.');
		} catch (Exception $e) {
			return redirect()->back()->with('error', $e->getMessage());
		}
	}

	public function validateData(Request $req)
	{

	}

	// public function updateStatus(Request $req)
	// {
	// 	try {
	// 		$this->service->updateStatus($req);
	// 		return redirect()->route('departments.list')->with('success', 'Department status updated successfully.');
	// 	} catch (Exception $e) {
	// 		return redirect()->back()->with('error', 'Failed to update status: ' . $e->getMessage());
	// 	}
	// }

	public function updateStatus(Request $req)
{
    try {
        $id = base64_decode($req->id);
        $department = $this->service->edit($id);

        if (!$department) {
            return response()->json(['status' => false, 'message' => 'Department not found'], 404);
        }

        $is_active = $req->is_active ? 1 : 0;
        $this->service->updateStatus($req);

        $statusText = $is_active ? 'activated' : 'deactivated';
        return response()->json([
            'status' => true,
            'message' => "Department '{$department->department_name}' status {$statusText} successfully"
        ]);

    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
    }
}

public function export(Request $request)
{
    $search = $request->query('search');
    $type = $request->query('type') ?? 'excel'; // optional type for pdf/excel

    $query = \DB::table('departments')
        ->join('plant_masters', 'departments.plant_id', '=', 'plant_masters.id')
        ->where('departments.is_deleted', 0) // 👈 fully qualified
        ->select('departments.*', 'plant_masters.plant_name');

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('departments.department_name', 'like', "%$search%")
              ->orWhere('departments.department_code', 'like', "%$search%")
              ->orWhere('plant_masters.plant_name', 'like', "%$search%");
        });
    }

    $departments = $query->get();

    if ($departments->isEmpty()) {
        return redirect()->back()->with('error', 'No data available to export.');
    }

    if ($type == 'excel') {
        $fileName = 'departments_' . date('Y_m_d') . '.xlsx';
        return Excel::download(new DepartmentsExport($search), $fileName);
    }

    if ($type == 'pdf') {
        $pdf = Pdf::loadView('superadm.departments.pdf', compact('departments'))
                  ->setPaper('A4', 'landscape'); // for better width
        return $pdf->download('departments_' . date('Y_m_d') . '.pdf');
    }

    return redirect()->back()->with('error', 'Invalid export type.');
}




// public function updateStatus(Request $req)
// {
//     try {
//         $id = base64_decode($req->id);
//         $department = $this->service->edit($id);

//         if (!$department) {
//             return response()->json(['status' => false, 'message' => 'Department not found'], 404);
//         }

//         $is_active = $req->is_active ? 1 : 0;

//         // ✅ If trying to deactivate, check for employees
//         if ($is_active == 0) {
//             $employeeExists = \DB::table('employees')
//                 ->where('department_id', $id)
//                 ->where('is_deleted', 0)
//                 ->exists();

//             if ($employeeExists) {
//                 return response()->json([
//                     'status' => false,
//                     'message' => "Cannot deactivate the department '{$department->department_name}' because employees are assigned to it."
//                 ], 400);
//             }
//         }

//         // ✅ Update status
//         $this->service->updateStatus($req);

//         $statusText = $is_active ? 'activated' : 'deactivated';
//         return response()->json([
//             'status' => true,
//             'message' => "Department '{$department->department_name}' status {$statusText} successfully"
//         ]);

//     } catch (\Exception $e) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Failed to update status: ' . $e->getMessage()
//         ], 500);
//     }
// }


}
