<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Departments;
use App\Models\Projects;

class EmployeePlantAssignment extends Model
{
    use HasFactory;

    protected $table = 'employee_plant_assignments';
    protected $fillable = [
        'employee_id',
        'plant_id',
        'department_id',
        'projects_id',
        'is_active',
        'is_deleted',
        'send_api',
        'send_api_department_id',
        'send_api_role_id'
    ];

    protected $casts = [
        'department_id'     => 'array',
        'projects_id'       => 'array',
        'send_api_role_id'  => 'array'
    ];

    // Relationships
    public function employee() {
        return $this->belongsTo(Employees::class, 'employee_id');
    }

    public function plant() {
        return $this->belongsTo(PlantMasters::class, 'plant_id');
    }

    // Accessors for department and project names
    // public function getDepartmentsNamesAttribute() {
    //     $ids = $this->department_id; // Already an array
    //     $ids = is_array($ids) ? $ids : [];
    //     if(empty($ids)) return '-';
    //     return Departments::whereIn('id', $ids)->pluck('department_name')->implode(', ');
    // }

    public function getDepartmentsNamesAttribute()
{
    $ids = is_array($this->department_id) ? $this->department_id : [];

    if (empty($ids)) {
        return '-';
    }

    return Departments::whereIn('id', $ids)
        ->get()
        ->map(function ($dept) {
            return $dept->department_name . ' (' . $dept->department_code . ')';
        })
        ->implode(', ');
}


    public function getProjectsNamesAttribute() {
        $ids = $this->projects_id; // Already an array
        $ids = is_array($ids) ? $ids : [];
        if(empty($ids)) return '-';
        return Projects::whereIn('id', $ids)->pluck('project_name')->implode(', ');
    }




}
