<?php
namespace App\Http\ServiceAll\Superadm\Dashboard;
use Illuminate\Http\Request;
use App\Http\RepositoryAll\Superadm\Dashboard\DashboardRepository;

class DashboardService
{
    public function __construct()
    {
    	$this->repositoryRepo=new DashboardRepository();
    }


}