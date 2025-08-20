<?php

namespace App\Http\Repository\Superadm;

use Illuminate\Http\Request;
use App\Models\Employees;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;

class EmployeesRepository
{
    public function list()
    {
        try {
            return Employees::where('is_deleted', 0)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $e) {
            Log::error("Error fetching project list: " . $e->getMessage());
            return collect(); // return empty collection on error
        }
    }

    public function save($data)
    {
        try {
            return Employees::create($data);
        } catch (Exception $e) {
            Log::error("Error saving project: " . $e->getMessage());
            return false;
        }
    }

    public function edit($id)
    {
        try {
            return Employees::where('id', $id)->first();
        } catch (Exception $e) {
            Log::error("Error editing project ID {$id}: " . $e->getMessage());
            return null;
        }
    }

    public function update($data, $id)
    {
        try {
            return Employees::where('id', $id)->update($data);
        } catch (Exception $e) {
            Log::error("Error updating project ID {$id}: " . $e->getMessage());
            return false;
        }
    }

    public function delete($data, $id)
    {
        try {
            return Employees::where('id', $id)->update($data);
        } catch (Exception $e) {
            Log::error("Error deleting project ID {$id}: " . $e->getMessage());
            return false;
        }
    }

    public function updateStatus($data, $id)
    {
        try {
            return Employees::where('id', $id)->update($data);
        } catch (Exception $e) {
            Log::error("Error updating status for project ID {$id}: " . $e->getMessage());
            return false;
        }
    }
}
