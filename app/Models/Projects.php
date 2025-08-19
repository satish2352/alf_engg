<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
     public $table = 'projects';
    public $timestamps = true;
    protected $fillable = ['project_name','project_description','project_url', 'is_active'];
}
