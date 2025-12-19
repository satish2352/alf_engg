<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designations extends Model
{
    public $table = 'designations';
    public $timestamps = true;
    protected $fillable = ['designation','short_description', 'designation_code', 'send_api', 'send_api_project_id', 'is_active'];

    public function employees()
    {
        return $this->hasMany(Employees::class, 'designation_id');
    }

}
