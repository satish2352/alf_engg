<?php

namespace App\Http\Controllers\Superadm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Superadm\EmployeePlantAssignmentService;
use App\Models\Employees;
use App\Models\PlantMasters;
use App\Models\Departments;
use App\Models\EmployeePlantAssignment;
use App\Models\Projects;
use Illuminate\Support\Facades\Validator;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeePlantAssignmentsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;

class EmployeePlantAssignmentController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new EmployeePlantAssignmentService();
    }

    // List all assignments
    public function index()
    {
        try {
            $assignments = $this->service->list();
            return view('superadm.employee_assignments.list', compact('assignments'));  
        } catch (Exception $e) {
            return back()->with('error', 'Error fetching assignments: ' . $e->getMessage());
        }
    }

    // Show create form
    public function create()
    {
        try {
            $employees = Employees::where('is_active',1)->where('is_deleted',0)->where('employee_name', '!=', '0')->whereNotNull('employee_name')->get();
            $plants = PlantMasters::where('is_active',1)->where('is_deleted',0)->get();
            $departments = Departments::where('is_active',1)->where('is_deleted',0)->get();
            $projects = Projects::where('is_active',1)->where('is_deleted',0)->get();

            return view('superadm.employee_assignments.create', compact('employees','plants','departments','projects'));
        } catch (Exception $e) {
            return back()->with('error', 'Error loading form: ' . $e->getMessage());
        }
    }

    // Save new assignment
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id'   => 'required',
            'plant_id'      => 'required',
            'department_id' => 'required|array|min:1',
            'projects_id'   => 'required|array|min:1',
        ], [
            'employee_id.required'   => 'Please Select An Employee',
            'plant_id.required'      => 'Please Select a Plant',
            'department_id.required' => 'Please Select At Least One Department',
            'projects_id.required'   => 'Please Select At Least One Project',
        ]);

        $validator->validate();

        try {
            // Check for duplicate employee + plant
            $exists = $this->service->exists($request->employee_id, $request->plant_id);
            if($exists){
                $employeeName = Employees::find($request->employee_id)->employee_name;
                return back()->withInput()->with('error', "$employeeName Is Already Assigned To The Selected Plant.");
            }

            // Save assignment
            $this->service->save($request);

            $employeeName = Employees::find($request->employee_id)->employee_name;
            $plantName = PlantMasters::find($request->plant_id)->plant_name;

            return redirect()->route('employee.assignments.list')
                             ->with('success', "$employeeName Has Been Assigned To $plantName Plant Successfully.");

        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Error adding assignment: ' . $e->getMessage());
        }
    }

    public function sendApi(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        try {
            $assignment = EmployeePlantAssignment::with(['employee', 'plant'])
                            ->findOrFail($request->id);

            $employee = $assignment->employee;
            $plant = $assignment->plant;

            // Get department codes
            $departmentCodes = Departments::whereIn('id', $assignment->department_id ?? [])
                        ->pluck('department_code')
                        ->implode(',');

            // Get projects
            $projectIds = $assignment->projects_id ?? [];
            $projects = Projects::whereIn('id', $projectIds)->get();

            $responses = [];

            foreach ($projects as $proj) {
                $payload = [
                    'plant'          => $plant->plant_code,
                    'dept'     => $departmentCodes,
                    'email_id'      => $employee->employee_email,
                    'role'                => $employee->role->role ?? '',
                    'emp_name'       => $employee->employee_name,
                    'emp_code'       => $employee->employee_code,
                    'emp_type'           => $employee->employee_type,
                    'username'  => $employee->employee_user_name,
                    'password'  => decrypt($employee->plain_password ?? ''),
                    'status'         => $assignment->is_active,
                    // 'password'   => $employee->employee_password, // hashed
                    // 'project_id'          => $proj->id, // optional
                ];

                    // Extract project name dynamically from project_url
                    $projectName = '';
                    if (preg_match('#https?://[^/]+/([^/]+)/#', $proj->project_url, $matches)) {
                        $projectName = $matches[1]; // This will be like 'alfkaizen' 
                    }
                    // Log the project name
                    // \Log::info('Project Name: ' . $projectName);

                    // Send POST request and capture response
                    // $response = Http::post('https://alfitworld.com/alfkaizen/CommonController/api_add_employee', $payload);

                    // Build the API URL dynamically
                    $apiUrl = "https://alfitworld.com/{$projectName}/CommonController/api_add_employee";

                    // Send POST request
                    $response = Http::post($apiUrl, $payload);

                $responses[] = [
                    'project_id' => $proj->id,
                    'payload'    => $payload,
                    'status'     => $response->successful() ? 'success' : 'failed',
                    'response'   => $response->body(),
                    'function'   => 'sendApi'
                ];
            }

            return response()->json([
                'status' => true,
                'message' => 'API call sent successfully for ' . $employee->employee_name,
                'data' => $responses
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    // Show edit form
    public function edit($encodedId)
    {
        try {
            $id = base64_decode($encodedId);
            $assignment = $this->service->getById($id);

            $employees = Employees::where('is_active',1)->where('is_deleted',0)->where('employee_name', '!=', '0')->whereNotNull('employee_name')->get();
            $plants = PlantMasters::where('is_active',1)->where('is_deleted',0)->get();
            $departments = Departments::where('is_active',1)->where('is_deleted',0)->get();
            $projects = Projects::where('is_active',1)->where('is_deleted',0)->get();

            return view('superadm.employee_assignments.edit', compact(
                'assignment','employees','plants','departments','projects','encodedId'
            ));
        } catch(Exception $e) {
            return back()->with('error', 'Error loading edit form: ' . $e->getMessage());
        }
    }

    // Update assignment
    public function update(Request $request, $encodedId)
    {
        $id = base64_decode($encodedId);

        $validator = Validator::make($request->all(), [
            'employee_id'   => 'required',
            'plant_id'      => 'required',
            'department_id' => 'required|array|min:1',
            'projects_id'   => 'required|array|min:1',
            'is_active'     => 'required|in:0,1',
        ], [
            'employee_id.required'   => 'Please Select An Employee',
            'plant_id.required'      => 'Please Select a Plant',
            'department_id.required' => 'Please Select At Least One Department',
            'projects_id.required'   => 'Please Select At Least One Project',
            'is_active.required'     => 'Please Select Status',
        ]);

        $validator->validate();

        try {
            // Check for duplicate, excluding current record
            $exists = $this->service->exists($request->employee_id, $request->plant_id, $id);
            if($exists){
                $employeeName = Employees::find($request->employee_id)->employee_name;
                return back()->withInput()->with('error', "$employeeName Is Already Assigned To The Selected Plant.");
            }

            // Update assignment
            $this->service->update($request, $id);

            $employeeName = Employees::find($request->employee_id)->employee_name;
            $plantName = PlantMasters::find($request->plant_id)->plant_name;

            return redirect()->route('employee.assignments.list')
                             ->with('success', "$employeeName Assigned Has Been Updated To $plantName Plant Successfully.");

        } catch(Exception $e) {
            return back()->withInput()->with('error', 'Error updating assignment: ' . $e->getMessage());
        }
    }

    // Delete assignment
    public function delete(Request $request)
    {
        try {
            $id = base64_decode($request->id);
            $assignment = $this->service->getById($id);
            $employeeName = $assignment->employee->employee_name;
            $plantName = $assignment->plant->plant_name;

            $this->service->delete($request);

            return response()->json([
                'status'=>true,
                'message'=> "$employeeName Assignment For $plantName Plant Has Been Deleted Successfully."
            ]);
        } catch(Exception $e) {
            return response()->json(['status'=>false,'message'=>'Error deleting assignment: '.$e->getMessage()]);
        }
    }

    // Update status (Active / Inactive)
    // public function updateStatus(Request $request)
    // {
    //     try {
    //         $id = base64_decode($request->id);
    //         $assignment = $this->service->getById($id);
    //         $employeeName = $assignment->employee->employee_name;
    //         $plantName = $assignment->plant->plant_name;

    //         $this->service->updateStatus($request);

    //         $statusText = $request->is_active == 1 ? 'Activated' : 'Deactivated';
    //         return response()->json([
    //             'status'=>true,
    //             'message'=> "$employeeName Assignment For $plantName Plant has been $statusText Successfully."
    //         ]);
    //     } catch(Exception $e) {
    //         return response()->json(['status'=>false,'message'=>'Error updating status: '.$e->getMessage()]);
    //     }
    // }

    public function updateStatus(Request $request)
    {
        try {
            $id = base64_decode($request->id);
            $assignment = $this->service->getById($id);
            $employeeName = $assignment->employee->employee_name;
            $plantName = $assignment->plant->plant_name;

            // First update status in DB
            $this->service->updateStatus($request);

            $employee = $assignment->employee;
            $plant = $assignment->plant;

            $departmentCodes = Departments::whereIn('id', $assignment->department_id ?? [])
                        ->pluck('department_code')
                        ->implode(',');

            $projectIds = $assignment->projects_id ?? [];
            $projects = Projects::whereIn('id', $projectIds)->get();

            $responses = [];  // Store each API result

            foreach ($projects as $proj) {

                $payload = [
                    'plant'          => $plant->plant_code,
                    'dept'           => $departmentCodes,
                    'email_id'       => $employee->employee_email,
                    'role'           => $employee->role->role ?? '',
                    'emp_name'       => $employee->employee_name,
                    'emp_code'       => $employee->employee_code,
                    'emp_type'       => $employee->employee_type,
                    'username'       => $employee->employee_user_name,
                    'password'       => decrypt($employee->plain_password ?? ''),
                    'status'         => $request->is_active, 
                ];

                // Extract project name
                $projectName = '';
                if (preg_match('#https?://[^/]+/([^/]+)/#', $proj->project_url, $matches)) {
                    $projectName = $matches[1];
                }

                $apiUrl = "https://alfitworld.com/{$projectName}/CommonController/api_add_employee";

                // API CALL
                $response = Http::post($apiUrl, $payload);

                // Store response details
                $responses[] = [
                    'project_id' => $proj->id,
                    'url'        => $apiUrl,
                    'payload'    => $payload,
                    'status'     => $response->successful() ? 'success' : 'failed',
                    'response'   => $response->body(),
                ];
            }

            $statusText = $request->is_active == 1 ? 'Activated' : 'Deactivated';

            return response()->json([
                'status' => true,
                'message' => "$employeeName Assiged For $plantName Plant has been $statusText Successfully.",
                'api_responses' => $responses  // Here you get all API responses
            ]);

        } catch(Exception $e) {
            return response()->json([
                'status'=>false,
                'message'=>'Error updating status: '.$e->getMessage()
            ]);
        }
    }


public function export(Request $request)
{
    $type = $request->query('type', 'excel'); // default excel

    // Get assignments (consider adding search filters later if needed)
    $assignments = EmployeePlantAssignment::where('is_deleted', 0)->get();

    if ($assignments->isEmpty()) {
        return redirect()->back()->with('error', 'No data available to export.');
    }

    if ($type === 'excel') {
        return Excel::download(new EmployeePlantAssignmentsExport, 'EmployeeAssignments.xlsx');
    }

    $pdf = Pdf::loadView('superadm.employee_assignments.assignments_pdf', compact('assignments'))
            ->setPaper('A4', 'landscape');
    return $pdf->download('EmployeeAssignments.pdf');

    return redirect()->back()->with('error', 'Invalid export type.');
}


}
