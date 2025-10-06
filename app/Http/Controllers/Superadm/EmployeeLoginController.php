<?php

namespace App\Http\Controllers\Superadm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Employees;
use App\Models\EmployeePlantAssignment;
use Illuminate\Support\Facades\Session;
use App\Models\FinancialYear;

class EmployeeLoginController extends Controller
{
    public function loginEmployee()
    {
        $financialYears = FinancialYear::where('is_deleted', 0)
            ->where('is_active', 1)
            ->orderBy('year', 'desc')
            ->get();

        return view('superadm.emp-login', compact('financialYears'));
    }

    public function validateEmpLogin(Request $req)
    {
        $req->validate([
            'superemail' => 'required|string',
            'superpassword' => 'required',
            'plant_id' => 'required',
            'financial_year_id' => 'required',
        ], [
            'superemail.required' => 'Enter user name',
            'superpassword.required' => 'Enter password',
            'plant_id.required' => 'Please select a plant',
            'financial_year_id.required' => 'Please select financial year',
        ]);

        $uname = $req->input('superemail');
        $pass = $req->input('superpassword');
        $plant = $req->input('plant_id');

        $employee = Employees::where('employee_user_name', $uname)
                             ->where('is_deleted', 0)
                             ->first();

        if (!$employee) return redirect()->back()->with('error', 'Credentials incorrect');
        if ($employee->is_active == 0) return redirect()->back()->with('error', 'Your account has been deactivated. Please contact the admin for assistance.');
        if (!Hash::check($pass, $employee->employee_password)) return redirect()->back()->with('error', 'Credentials incorrect');

        $hasPlant = EmployeePlantAssignment::where('employee_id', $employee->id)
                                           ->where('plant_id', $plant)
                                           ->where('is_active', 1)
                                           ->exists();

        if (!$hasPlant) return redirect()->back()->with('error', 'Selected plant not assigned');

        // ✅ Store session
        Session::put('emp_user_id', $employee->id);
        Session::put('emp_role_id', $employee->role_id);
        Session::put('emp_plant_id', $plant);
        Session::put('emp_code', $employee->employee_code);
        Session::put('emp_financial_year_id', $req->financial_year_id);
        Session::put('role', 'employee');
        Session::put('email_id', $employee->employee_email);

        return redirect()->route('dashboard-emp');
    }

    public function logOut(Request $req)
    {
        $req->session()->flush();
        return redirect()->route('emp.login');
    }
}
