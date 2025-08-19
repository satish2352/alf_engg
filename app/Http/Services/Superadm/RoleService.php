<?php
namespace App\Http\Services\Superadm;

use Illuminate\Http\Request;
use App\Http\Repository\Superadm\RoleRepository;
use Exception;
use Log;

class RoleService
{
    protected $repo;

    public function __construct()
    {
        $this->repo = new RoleRepository();
    }

    public function list()
    {
        try {
            return $this->repo->list();
        } catch (Exception $e) {
            Log::error("RoleService list error: " . $e->getMessage());
            return false;
        }
    }

    public function save($req)
    {
        try {
            $data = ['role' => $req->input('role')];
            return $this->repo->save($data);
        } catch (Exception $e) {
            Log::error("RoleService save error: " . $e->getMessage());
            return false;
        }
    }

    public function edit($id)
    {
        try {
            return $this->repo->edit($id);
        } catch (Exception $e) {
            Log::error("RoleService edit error: " . $e->getMessage());
            return false;
        }
    }

    public function update($req)
    {
        try {
            $id = $req->id;
            $data = [
                'role' => $req->input('role'),
                'is_active' => $req->is_active
            ];

            return $this->repo->update($data, $id);
        } catch (Exception $e) {
            Log::error("RoleService update error: " . $e->getMessage());
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
            Log::error("RoleService delete error: " . $e->getMessage());
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
            Log::error("RoleService updateStatus error: " . $e->getMessage());
            return false;
        }
    }
}
