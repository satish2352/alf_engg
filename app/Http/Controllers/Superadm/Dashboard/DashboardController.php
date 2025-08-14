<?php

namespace App\Http\Controllers\Superadm\Dashboard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\ServiceAll\Superadm\Dashboard\DashboardService;
use App\Models\Roles;

class DashboardController extends Controller
{
	function __construct()
	{
		$this->serviceAll=new DashboardService();
	}

	public function index()
	{
		$allRoles= Roles::where('is_active', 1)->count();;
		return view('superadm.dashboard.dashboard',compact('allRoles'));
	}
}
