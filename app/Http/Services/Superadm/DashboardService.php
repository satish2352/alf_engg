<?php
namespace App\Http\Service\Superadm;
use Illuminate\Http\Request;
use App\Http\Repository\Superadm\DashboardRepository;

class DashboardService
{
    public function __construct()
    {
    	$this->repo=new DashboardRepository();
    }


}