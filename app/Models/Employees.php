<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    public $table = 'employees';
    public $timestamps = true;
    // protected $fillable = ['plant_id','department_id','projects_id','designation_id','role_id','employee_code','employee_name','employee_type','employee_email','employee_user_name','employee_password','reporting_to', 'is_active'];
    protected $fillable = ['designation_id','role_id','employee_code','employee_name','employee_type','employee_email','employee_user_name','employee_password','reporting_to', 'is_active'];
    
    public function assignments()
    {
        return $this->hasMany(EmployeePlantAssignment::class, 'employee_id');
    }

    public function assignedPlants()
    {
        return $this->hasManyThrough(
            PlantMasters::class,
            EmployeePlantAssignment::class,
            'employee_id',      // FK on assignment table
            'id',               // PK on plant table
            'id',               // PK on employees table
            'plant_id'          // FK on assignment table to plant
        )->where('employee_plant_assignments.is_deleted', 0)
        ->where('plant_masters.is_active', 1);
    }

}


 