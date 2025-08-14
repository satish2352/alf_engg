<?php

namespace App\Http\ServiceAll\Superadm;

use Illuminate\Http\Request;
use App\Http\RepositoryAll\Superadm\LoginRepository;
use session;

class LoginService
{
    public function __construct()
    {
    	$this->repositoryAll=new LoginRepository();
    }

    public function validateSuperLogin($req,$uname,$pass)
    {
    	$dataOfLogin=$this->repositoryAll->validateSuperLogin($uname,$pass);
    	if(count($dataOfLogin)>0)
    	{

    		$req->session()->put('superLoginId',$dataOfLogin[0]->id);
    		return '1';
    	}
        else
        {
            dd("wrong user");
        }
    }



    public function validateSchoolAdmLogin($schoolId)
    {
        $result=$this->repositoryAll->getSchoolAdminLoginDetails($schoolId);
        foreach ($result as $key => $dataOfLogin) 
        {
            session()->put('schooladmLoginId',$dataOfLogin['schoolid']);
            session()->put('firstName',$dataOfLogin['firstName']);
            session()->put('lastName',$dataOfLogin['lastName']);
            session()->put('role',$dataOfLogin['role']);
            $schoolData=$this->repositoryAll->getSchoolCode($dataOfLogin['schoolid']);
            foreach ($schoolData as $key => $valueNew) 
            {
                session()->put('schoolCode',$valueNew['schoolCode']);
            }
            return Redirect(route('dashboardschool'));
            // print_r($_SESSION());
            // return view('schooladm.dashboard.dashboard');
            
         }
        
    }
}
