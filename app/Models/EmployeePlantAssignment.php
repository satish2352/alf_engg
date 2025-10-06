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
        'is_deleted'
    ];

    // Relationships
    public function employee() {
        return $this->belongsTo(Employees::class, 'employee_id');
    }

    public function plant() {
        return $this->belongsTo(PlantMasters::class, 'plant_id');
    }

    // Accessors for department and project names
    public function getDepartmentsNamesAttribute() {
        if(!$this->department_id) return '-';
        $ids = explode(',', $this->department_id);
        return Departments::whereIn('id', $ids)->pluck('department_name')->join(', ');
    }

    public function getProjectsNamesAttribute() {
        if(!$this->projects_id) return '-';
        $ids = explode(',', $this->projects_id);
        return Projects::whereIn('id', $ids)->pluck('project_name')->join(', ');
    }
}
