<?php
namespace App\Http\RepositoryAll;
use App\Model\SchoolAdm\StaffLogin;
use App\Model\Student\StudentLogin;
use DB;
class CommonRepositoryNew
{
    public function validateStaffLogin($uname,$pass)
    {
    	return StaffLogin::where([['emailid','=',$uname],['password','=',$pass]])->select('*')->get();
    }


    public function validateLiabraryLogin($uname,$pass)
    {
    	return StaffLogin::where([['emailid','=',$uname],['password','=',$pass]])->select('*')->get();
    } 

    public function validateStudentLogin($uname,$pass)
    {
    	return StudentLogin::where([['userId','=',$uname],['password','=',$pass]])->select('*')->get();
    }
}

