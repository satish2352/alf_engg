<?php

namespace App\Http\Controllers\Superadm;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\ServiceAll\Superadm\LoginService;
use Illuminate\Foundation\Validation\ValidatesRequests;

class LoginController extends Controller
{
   public function __construct()
	{
		$this->serviceAll=new LoginService();
	}

   public function loginsuper()
   {
   	return view('superadm.login');
   }

   public function validateSuperLogin(Request $req)
   {
		// $this->validateLogin($req);
		$uname=$req->input('superemail');
		$pass=$req->input('superpassword');
		$result=$this->serviceAll->validateSuperLogin($req,$uname,$pass);
      if($result=='1')
      {
         //return view('superadm.dashboard.dashboard');
         return redirect('dashboard');
      }
      else
      {
         return route('super');
      }

   }

   public function validateLogin(Request $req)						
   {
		$this->validate($req,[
			'superemail.required|email',
			'superpassword.required'
		],
      [
         'superemail.email'=>'Enter proper email adddress',
         'superemail.required'=>'Enter email adddress',
         'superpassword.required'=>'Enter password'
      ]

      );
   }

   public function logOut(Request $req)
   {
      $req->session()->forget('superLoginId');
      return redirect('super');
   }


   public function autoLoginSchoolAdmin($schoolId)
   {
      $result=$this->serviceAll->validateSchoolAdmLogin($schoolId);
   }
   
}
