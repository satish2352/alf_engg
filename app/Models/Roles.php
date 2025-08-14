<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    public $timestamps = true;
    protected $fillable = ['role', 'is_active'];
}
