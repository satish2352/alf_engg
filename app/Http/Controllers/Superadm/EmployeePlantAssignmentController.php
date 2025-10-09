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
            'employee_id.required'   => 'Please select an employee',
            'plant_id.required'      => 'Please select a plant',
            'department_id.required' => 'Please select at least one department',
            'projects_id.required'   => 'Please select at least one project',
        ]);

        $validator->validate();

        try {
            // Check for duplicate employee + plant
            $exists = $this->service->exists($request->employee_id, $request->plant_id);
            if($exists){
                $employeeName = Employees::find($request->employee_id)->employee_name;
                return back()->withInput()->with('error', "$employeeName is already assigned to the selected plant.");
            }

            // Save assignment
            $this->service->save($request);

            $employeeName = Employees::find($request->employee_id)->employee_name;
            $plantName = PlantMasters::find($request->plant_id)->plant_name;

            return redirect()->route('employee.assignments.list')
                             ->with('success', "$employeeName has been assigned to $plantName plant successfully.");

        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Error adding assignment: ' . $e->getMessage());
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
            'employee_id.required'   => 'Please select an employee',
            'plant_id.required'      => 'Please select a plant',
            'department_id.required' => 'Please select at least one department',
            'projects_id.required'   => 'Please select at least one project',
            'is_active.required'     => 'Please select status',
        ]);

        $validator->validate();

        try {
            // Check for duplicate, excluding current record
            $exists = $this->service->exists($request->employee_id, $request->plant_id, $id);
            if($exists){
                $employeeName = Employees::find($request->employee_id)->employee_name;
                return back()->withInput()->with('error', "$employeeName is already assigned to the selected plant.");
            }

            // Update assignment
            $this->service->update($request, $id);

            $employeeName = Employees::find($request->employee_id)->employee_name;
            $plantName = PlantMasters::find($request->plant_id)->plant_name;

            return redirect()->route('employee.assignments.list')
                             ->with('success', "$employeeName assigned has been updated to $plantName plant successfully.");

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
                'message'=> "$employeeName assignment for $plantName plant has been deleted successfully."
            ]);
        } catch(Exception $e) {
            return response()->json(['status'=>false,'message'=>'Error deleting assignment: '.$e->getMessage()]);
        }
    }

    // Update status (Active / Inactive)
    public function updateStatus(Request $request)
    {
        try {
            $id = base64_decode($request->id);
            $assignment = $this->service->getById($id);
            $employeeName = $assignment->employee->employee_name;
            $plantName = $assignment->plant->plant_name;

            $this->service->updateStatus($request);

            $statusText = $request->is_active == 1 ? 'activated' : 'deactivated';
            return response()->json([
                'status'=>true,
                'message'=> "$employeeName's assignment for $plantName plant has been $statusText successfully."
            ]);
        } catch(Exception $e) {
            return response()->json(['status'=>false,'message'=>'Error updating status: '.$e->getMessage()]);
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
