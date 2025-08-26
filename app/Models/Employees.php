<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    public $table = 'employees';
    public $timestamps = true;
    protected $fillable = ['plant_id','department_id','projects_id','designation_id','role_id','employee_code','employee_name','employee_type','employee_email','employee_user_name','employee_password', 'is_active'];
}


 