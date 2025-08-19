<?php
namespace App\Http\Services\Superadm;

use Illuminate\Http\Request;
use App\Http\Repository\Superadm\DesignationsRepository;
use Exception;
use Log;

class DesignationsService
{
    protected $repo;

    public function __construct()
    {
        $this->repo = new DesignationsRepository();
    }

    public function list()
    {
        try {
            return $this->repo->list();
        } catch (Exception $e) {
            Log::error("Designation Service list error: " . $e->getMessage());
            return false;
        }
    }

    public function save($req)
    {
        try {
            $data = ['designation' => $req->input('designation'), 'short_description' => $req->input('short_description')];
            return $this->repo->save($data);
        } catch (Exception $e) {
            Log::error("Designation Service save error: " . $e->getMessage());
            return false;
        }
    }

    public function edit($id)
    {
        try {
            return $this->repo->edit($id);
        } catch (Exception $e) {
            Log::error("Designation Service edit error: " . $e->getMessage());
            return false;
        }
    }

    public function update($req)
    {

        try {
            $id = $req->id;
            $data = [
                'designation' => $req->input('designation'),
                'short_description' => $req->input('short_description'),
                'is_active' => $req->is_active
            ];

            return $this->repo->update($data, $id);
        } catch (Exception $e) {
            Log::error("Designation Service update error: " . $e->getMessage());
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
            Log::error("Designation Service delete error: " . $e->getMessage());
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
            Log::error("Designation Service updateStatus error: " . $e->getMessage());
            return false;
        }
    }
}
