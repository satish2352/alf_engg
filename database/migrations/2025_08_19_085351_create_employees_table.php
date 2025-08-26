<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('plant_id');
            $table->string('department_id');
            $table->string('projects_id');
            $table->string('designation_id');
            $table->string('role_id');
            $table->string('employee_code');
            $table->string('employee_name');
            $table->string('employee_type');
            $table->string('employee_email');
            $table->string('employee_user_name');
            $table->string('employee_password');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
