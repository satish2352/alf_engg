<?php

namespace App\Http\Controllers\Superadm;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Hash;
use App\Models\Employees;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

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
        $req->validate([
            'superemail' => 'required|email',
            'superpassword' => 'required',
            'g-recaptcha-response' => 'required',
        ], [
            'superemail.required' => 'Enter email address',
            'superemail.email' => 'Enter a proper email address',
            'superpassword.required' => 'Enter password',
            'g-recaptcha-response.required' => 'Please verify that you are not a robot',
        ]);

        // Verify Google reCAPTCHA
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $req->input('g-recaptcha-response'),
            'remoteip' => $req->ip(),
        ]);

        $result = $response->json();
        if (!($result['success'] ?? false)) {
            return back()->withErrors(['g-recaptcha-response' => 'Captcha verification failed'])->withInput();
        }

        // Check user credentials
        $uname = $req->input('superemail');
        $pass = $req->input('superpassword');

        $result = Employees::where('employee_user_name', $uname)
            ->where('is_deleted', 0)
            ->first();

        if (!$result) {
            return redirect()->back()->with('error', 'User not found, contact admin');
        }

        if ($result->is_active == 0) {
            return redirect()->back()->with('error', 'User account is deactivated. Please contact admin.');
        }

        if (!Hash::check($pass, $result->employee_password)) {
            return redirect()->back()->with('error', 'User credentials not matching with records');
        }

        // Set session
        Session::put('user_id', $result->id);
        Session::put('role_id', $result->role_id);
        Session::put('role', $result->role_id == 0 ? 'admin' : 'notadmin');
        Session::put('email_id', $result->employee_email);
        Session::put('department_id', explode(",", $result->department_id));
        Session::put('projects_id', explode(",", $result->projects_id));

        return $result->role_id == 0 ? redirect('dashboard') : redirect('dashboard-emp');
    }
   // public function validateSuperLogin(Request $req)
   // {
   //    $this->validateLogin($req);
   //    $uname = $req->input('superemail');
   //    $pass = $req->input('superpassword');
   //    $result = Employees::where('employee_user_name', $uname)
   //       ->where('is_deleted', 0)
   //       ->where('is_active', 1)
   //       ->first();
   //    if ($result) {
   //       if (Hash::check($pass, $result->employee_password)) {

   //          Session::put('user_id', $result->id);
   //          Session::put('role_id', $result->role_id);
   //          if($result->role_id == 0) {
   //             Session::put('role', 'admin');
   //          } else {
   //             Session::put('role', 'notadmin');
   //          }
   //          Session::put('email_id', $result->employee_email);
   //          Session::put('department_id', explode(",",$result->department_id) );
   //          Session::put('projects_id', explode(",",$result->projects_id));
   //          if($result->role_id == 0) {
   //             return redirect('dashboard');
   //          } else {
   //             return redirect('dashboard-emp');
   //          }
            
   //       } else {
   //          return redirect()->back()->with('error', 'User credentials not matching with records');
   //       }
   //    } else {
   //       return redirect()->back()->with('error', 'User not found contact to admin');
   //    }

   // }

   // public function validateLogin(Request $req)
   // {
   //    $req->validate(
   //       [
   //          'superemail.required|email',
   //          'superpassword.required'
   //       ],
   //       [
   //          'superemail.email' => 'Enter proper email adddress',
   //          'superemail.required' => 'Enter email adddress',
   //          'superpassword.required' => 'Enter password'
   //       ]

   //    );
   // }

   public function logOut(Request $req)
   {
      $req->session()->flush();
      return redirect('login');
   }

}
