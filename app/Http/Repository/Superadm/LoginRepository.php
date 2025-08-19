<?php

namespace App\Http\Repository\Superadm;
use App\Models\SuperAdm\SuperLogin;
use App\Models\SuperAdm\MSchool;
use DB;

class LoginRepository
{
    public function validateSuperLogin($uname,$pass)
    {
    	return SuperLogin::where([['emailid','=',$uname],['password','=',$pass],['role','=','1']])->select('*')->get();
    }

}

