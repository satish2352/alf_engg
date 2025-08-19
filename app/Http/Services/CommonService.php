<?php
namespace App\Http\ServiceAll;
use Illuminate\Http\Request;
use App\Http\RepositoryAll\CommonRepositoryNew;
use session;
use DB;

class CommonService
{
    
    public function __construct()
    {
    	$this->repositoryAll=new CommonRepositoryNew();
    }

    public function validateStaffLogin($req,$uname,$pass)
    {
    	$dataOfLogin=$this->repositoryAll->validateStaffLogin($uname,$pass);
    	if(count($dataOfLogin)>0)
    	{
    		$req->session()->put('staffId',$dataOfLogin[0]->id);
            $req->session()->put('staffSchoolId',$dataOfLogin[0]->schoolid);
            $req->session()->put('schoolIdGlobalVar',$dataOfLogin[0]->schoolid);
    		return '1';
    	}
        else
        {
            return redirect('/');
        }
    }


    public function validateLiabraryLogin($req,$uname,$pass)
    {
        $dataOfLogin=$this->repositoryAll->validateLiabraryLogin($uname,$pass);
        if(count($dataOfLogin)>0)
        {
            $req->session()->put('liabraryId',$dataOfLogin[0]->id);
            $req->session()->put('schoolIdGlobalVar',$dataOfLogin[0]->id);
            return '1';
        }
        else
        {
            return redirect('/');
        }
    }

    public function validateStudentLogin($req,$uname,$pass)
    {
        $dataOfLogin=$this->repositoryAll->validateStudentLogin($uname,$pass);
        if(count($dataOfLogin)>0)
        {
           
            $req->session()->put('studentId',$dataOfLogin[0]->id);
            $schoolIdfinal=DB::select("SELECT `schoolid` FROM `u_studentclassmapping` WHERE `studeLoginId`='".$dataOfLogin[0]->id."' ");
            $req->session()->put('studentSchoolId',$schoolIdfinal[0]->schoolid);
            $req->session()->put('schoolIdGlobalVar',$schoolIdfinal[0]->schoolid);
            
            return '1';
        }
        else
        {
            return redirect('/');
        }
    }
}
