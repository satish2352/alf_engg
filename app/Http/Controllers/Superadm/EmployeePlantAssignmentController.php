<?php

namespace App\Http\Controllers\Superadm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Superadm\EmployeePlantAssignmentService;
use App\Models\Employees;
use App\Models\PlantMasters;
use App\Models\Departments;
use App\Models\Projects;
use Illuminate\Support\Facades\Validator;
use Exception;

class EmployeePlantAssignmentController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new EmployeePlantAssignmentService();
    }

    public function index()
    {
        try {
            $assignments = $this->service->list();
            return view('superadm.employee_assignments.list', compact('assignments'));  
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        $employees = Employees::where('is_active',1)->where('is_deleted',0)->where('employee_name', '!=', '0')->whereNotNull('employee_name')->get();
        $plants = PlantMasters::where('is_active',1)->where('is_deleted',0)->get();
        $departments = Departments::where('is_active',1)->where('is_deleted',0)->get();
        $projects = Projects::where('is_active',1)->where('is_deleted',0)->get();

        return view('superadm.employee_assignments.create', compact('employees','plants','departments','projects'));
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'plant_id' => 'required',
            'department_id' => 'required|array',
            'projects_id' => 'required|array',
        ], [
            'employee_id.required' => 'Please select an employee',
            'plant_id.required' => 'Please select a plant',
            'department_id.required' => 'Please select at least one department',
            'projects_id.required' => 'Please select at least one project',
        ]);

        $validator->validate();

        try {
            $this->service->save($request);
            return redirect()->route('employee.assignments.list')->with('success','Assignment added successfully.');
        } catch(Exception $e) {
            return back()->withInput()->with('error','Something went wrong: '.$e->getMessage());
        }
    }


    public function edit($encodedId)
    {
        try {
            $id = base64_decode($encodedId);
            $assignment = $this->service->getById($id);

            $employees = Employees::where('is_active',1)->where('is_deleted',0)->where('employee_name', '!=', '0')->whereNotNull('employee_name')->get();
            $plants = PlantMasters::where('is_active',1)->where('is_deleted',0)->get();
            $departments = Departments::where('is_active',1)->where('is_deleted',0)->get();
            $projects = Projects::where('is_active',1)->where('is_deleted',0)->get();

            // Pass encodedId to the view
            return view('superadm.employee_assignments.edit', compact(
                'assignment','employees','plants','departments','projects','encodedId'
            ));
        } catch(Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


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
        $this->service->update($request, $id);
        return redirect()->route('employee.assignments.list')->with('success', 'Assignment updated successfully.');
    } catch (Exception $e) {
        return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}


    public function delete(Request $request)
    {
        try {
            $this->service->delete($request);
            return response()->json(['status'=>true,'message'=>'Assignment deleted successfully.']);
        } catch(Exception $e) {
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $this->service->updateStatus($request);
            return response()->json(['status'=>true,'message'=>'Status updated successfully.']);
        } catch(Exception $e) {
            return response()->json(['status'=>false,'message'=>$e->getMessage()]);
        }
    }
}
