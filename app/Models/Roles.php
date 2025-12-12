<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    public $timestamps = true;
    protected $fillable = ['role', 'short_description', 'send_api', 'send_api_project_id', 'is_active'];
}
