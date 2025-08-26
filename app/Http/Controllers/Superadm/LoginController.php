<?php

namespace App\Http\Controllers\Superadm;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Hash;
use App\Models\Employees;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
   public function __construct()
   {
   }

   public function loginsuper()
   {
      return view('superadm.login');
   }

   public function validateSuperLogin(Request $req)
   {
      $this->validateLogin($req);
      $uname = $req->input('superemail');
      $pass = $req->input('superpassword');
      $result = Employees::where('employee_user_name', $uname)
         ->where('is_deleted', 0)
         ->where('is_active', 1)
         ->first();
         
      if ($result) {
         if (Hash::check($pass, $result->employee_password)) {

            Session::put('user_id', $result->id);
            Session::put('role_id', $result->role_id);
            Session::put('email_id', $result->employee_email);
            Session::put('department_id', explode(",",$result->department_id) );
            Session::put('projects_id', explode(",",$result->projects_id));
            if(Session::get('user_id')===1) {
               return redirect('dashboard');
            } else {
               return redirect('dashboard-emp');
            }
            
         } else {
            return redirect()->back()->with('error', 'User credentials not matching with records');
         }
      } else {
         return redirect()->back()->with('error', 'User not found contact to admin');
      }

   }

   public function validateLogin(Request $req)
   {
      $req->validate(
         [
            'superemail.required|email',
            'superpassword.required'
         ],
         [
            'superemail.email' => 'Enter proper email adddress',
            'superemail.required' => 'Enter email adddress',
            'superpassword.required' => 'Enter password'
         ]

      );
   }

   public function logOut(Request $req)
   {
      $req->session()->flush();
      return redirect('login');
   }

}
