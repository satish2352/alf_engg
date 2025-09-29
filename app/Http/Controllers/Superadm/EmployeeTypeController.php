<?php
namespace App\Http\Controllers\Superadm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Superadm\EmployeeTypeService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rule;
use Exception;

class EmployeeTypeController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new EmployeeTypeService();
    }

    public function index()
    {
        try {
            $employeeTypes = $this->service->list();
            return view('superadm.employee_type.list', compact('employeeTypes'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function create(Request $req)
    {
        try {
            return view('superadm.employee_type.create');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function save(Request $req)
    {
        $req->validate([
            'type_name' => [
                'required',
                'max:255',
                'regex:/^[a-zA-Z0-9\s]+$/',
                Rule::unique('employee_types', 'type_name')->where(fn($query) => $query->where('is_deleted', 0)),
            ],
            'description' => 'required|max:255',
        ], [
            'type_name.required' => 'Enter Employee Type Name',
            'type_name.regex' => 'Employee Type must contain only letters, numbers, and spaces.',
            'type_name.unique' => 'This Employee Type already exists.',
            'type_name.max' => 'Employee Type name must not exceed 255 characters.',
            'description.required' => 'Enter Description',
            'description.max' => 'Description must not exceed 255 characters.',
        ]);

        try {
            $this->service->save($req);
            return redirect()->route('employee-types.list')->with('success', 'Employee Type added successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit($encodedId)
    {
        try {
            $id = base64_decode($encodedId);
            $data = $this->service->edit($id);
            return view('superadm.employee_type.edit', compact('data', 'encodedId'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function update(Request $req)
    {
        $req->validate([
            'type_name' => [
                'required',
                'max:255',
                Rule::unique('employee_types', 'type_name')
                    ->where(fn($query) => $query->where('is_deleted', 0))
                    ->ignore($req->id),
            ],
            'description' => 'required|max:255',
            'id' => 'required',
            'is_active' => 'required'
        ], [
            'type_name.required' => 'Enter Employee Type Name',
            'type_name.unique' => 'This Employee Type already exists.',
            'type_name.max' => 'Employee Type name must not exceed 255 characters.',
            'id.required' => 'ID required',
            'is_active.required' => 'Select active or inactive',
            'description.required' => 'Enter Description',
            'description.max' => 'Description must not exceed 255 characters.',
        ]);

        try {
            $this->service->update($req);
            return redirect()->route('employee-types.list')->with('success', 'Employee Type updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function delete(Request $req)
    {
        try {
            $req->validate(['id' => 'required'], ['id.required' => 'ID required']);
            $this->service->delete($req);
            return redirect()->route('employee-types.list')->with('success', 'Employee Type deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateStatus(Request $req)
    {
        try {
            $this->service->updateStatus($req);
            return redirect()->route('employee-types.list')->with('success', 'Employee Type status updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }
}
