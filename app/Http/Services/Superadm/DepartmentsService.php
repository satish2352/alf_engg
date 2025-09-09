<?php
namespace App\Http\Services\Superadm;

use Illuminate\Http\Request;
use App\Http\Repository\Superadm\DepartmentsRepository;
use Exception;
use Log;

class DepartmentsService
{
    protected $repo;

    public function __construct()
    {
        $this->repo = new DepartmentsRepository();
    }

    public function list()
    {
        try {
            return $this->repo->list();
        } catch (Exception $e) {
            Log::error("Department Service list error: " . $e->getMessage());
            return false;
        }
    }

     public function listajaxlist($req)
    {
        try {
            return $this->repo->listajaxlist($req['plant_id']);
        } catch (Exception $e) {
            Log::error("Department Service list error: " . $e->getMessage());
            return false;
        }
    }

    public function save($req)
    {

        try {
            $data = [   'plant_id' => $req->input('plant_id'), 
                        'department_code' => $req->input('department_code'),
                        'department_name' => $req->input('department_name'),
                    ];

                     if ($req->filled('department_short_name')) {
                        $data['department_short_name'] = $req->input('department_short_name');
                    }
            return $this->repo->save($data);
        } catch (Exception $e) {
            Log::error("Department Service save error: " . $e->getMessage());
            return false;
        }
    }

    public function edit($id)
    {
        try {
            return $this->repo->edit($id);
        } catch (Exception $e) {
            Log::error("Department Service edit error: " . $e->getMessage());
            return false;
        }
    }

    public function update($req)
    {

        try {
            $id = $req->id;
            $data = [
                'plant_id' => $req->input('plant_id'), 
                'department_code' => $req->input('department_code'),
                'department_name' => $req->input('department_name'),
                'is_active' => $req->is_active
            ];
             if ($req->filled('department_short_name')) {
                        $data['department_short_name'] = $req->input('department_short_name');
                    }

            return $this->repo->update($data, $id);
        } catch (Exception $e) {
            Log::error("Department Service update error: " . $e->getMessage());
            return false;
        }
    }

    public function delete($req)
    {
        try {
            $id = base64_decode($req->id);
            $data = ['is_deleted' => 1];

            return $this->repo->delete($data, $id);
        } catch (Exception $e) {
            Log::error("Department Service delete error: " . $e->getMessage());
            return false;
        }
    }

    public function updateStatus($req)
    {
        try {
            $id = base64_decode($req->id);
            $data = ['is_active' => $req->is_active];

            return $this->repo->updateStatus($data, $id);
        } catch (Exception $e) {
            Log::error("Department Service updateStatus error: " . $e->getMessage());
            return false;
        }
    }
}
