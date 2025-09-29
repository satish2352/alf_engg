<?php
namespace App\Http\Services\Superadm;

use Illuminate\Http\Request;
use App\Http\Repository\Superadm\PlantMasterRepository;
use Exception;
use Log;

class PlantMasterService
{
    protected $repo;

    public function __construct()
    {
        $this->repo = new PlantMasterRepository();
    }

    public function list()
    {
        try {
            return $this->repo->list();
        } catch (Exception $e) {
            Log::error("Plant Service list error: " . $e->getMessage());
            return false;
        }
    }

  public function save($req)
{
    try {
        $data = [
            'plant_code'       => $req->input('plant_code'),
            'plant_name'       => $req->input('plant_name'),
            'city'             => $req->input('city'),
        ];

        if ($req->filled('address')) {
            $data['address'] = $req->input('address');
        }

        if ($req->filled('plant_short_name')) {
            $data['plant_short_name'] = $req->input('plant_short_name');
        }

        return $this->repo->save($data);
    } catch (\Exception $e) {
        Log::error("Plant Service save error: " . $e->getMessage());
        return false;
    }
}


    public function edit($id)
    {
        try {
            return $this->repo->edit($id);
        } catch (Exception $e) {
            Log::error("Plant Service edit error: " . $e->getMessage());
            return false;
        }
    }

    public function update($req)
    {
        try {
            $id = $req->id;
            $data = [
                'plant_code' => $req->input('plant_code'),
                'plant_name' => $req->input('plant_name'),
                'city' => $req->input('city'),
                'is_active' => $req->is_active
            ];
            if ($req->filled('address')) {
            $data['address'] = $req->input('address');
        }

        if ($req->filled('plant_short_name')) {
            $data['plant_short_name'] = $req->input('plant_short_name');
        }


            return $this->repo->update($data, $id);
        } catch (Exception $e) {
            Log::error("Plant Service update error: " . $e->getMessage());
            return false;
        }
    }

    // public function delete($req)
    // {
    //     try {
    //         $id = base64_decode($req->id);
    //         $data = ['is_deleted' => 1];

    //         return $this->repo->delete($data, $id);
    //     } catch (Exception $e) {
    //         Log::error("Plant Service delete error: " . $e->getMessage());
    //         return false;
    //     }
    // }

    public function delete($req)
    {
        try {
            $id = base64_decode($req->id);
            $data = ['is_deleted' => 1];

            return $this->repo->delete($data, $id);
        } catch (Exception $e) {
            Log::error("Plant Service delete error: " . $e->getMessage());
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
            Log::error("Plant Service updateStatus error: " . $e->getMessage());
            return false;
        }
    }
}
