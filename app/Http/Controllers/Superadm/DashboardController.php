<?php
namespace App\Http\Controllers\Superadm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Service\Superadm\DashboardService;
use App\Models\Roles;
use App\Models\Designations;
use App\Models\PlantMasters;

class DashboardController extends Controller
{
	function __construct()
	{
		// $this->service=new DashboardService();
	}

	public function index()
	{
		$allRoles= Roles::where('is_deleted', 0)->count();
		$allDesignations = Designations::where('is_deleted', 0)->count();
		$allPlants = PlantMasters::where('is_deleted', 0)->count();
		return view('superadm.dashboard.dashboard',compact('allRoles', 'allDesignations', 'allPlants'));
	}
}
