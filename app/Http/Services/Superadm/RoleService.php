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

    public function sendApi($request)
{
    $role = \DB::table('roles')->where('id',$request->id)->where('is_deleted',0)->first();

    if(!$role){
        return response()->json(['status'=>false,'message'=>"Role not found"]);
    }

    $newProjects = $request->projects;
    $oldProjects = explode(",", $role->send_api_project_id ?? "");

    // 1️⃣ Removed Projects → Disable status=0
    $removed = array_diff($oldProjects, $newProjects);

    foreach($removed as $projId){

        $proj = \DB::table('projects')->where('id',$projId)->first();
        if(!$proj) continue;

        preg_match('#https?://[^/]+/([^/]+)/#',$proj->project_url,$m);
        $projectName = $m[1] ?? null;
        if(!$projectName) continue;

        \Http::post("https://alfitworld.com/{$projectName}/CommonController/changeRoleStatus",[
            "role" => $role->role,
            "status" => 0
        ]);
    }


    // 2️⃣ New + Existing Projects → Send/Update Role
    $projects = \DB::table('projects')->whereIn('id',$newProjects)->get();

    $allSuccess = true;

    foreach($projects as $proj){

        preg_match('#https?://[^/]+/([^/]+)/#',$proj->project_url,$m);
        $projectName = $m[1] ?? null;

        if(!$projectName){
            $allSuccess = false;
            continue;
        }

        $payload = [
            "role" => $role->role,
            "short_description" => $role->short_description
        ];

        $apiUrl = "https://alfitworld.com/{$projectName}/CommonController/addEditRoleApi";

        $response = \Http::post($apiUrl, $payload);

        if(!$response->successful()){
            $allSuccess = false;
        }
    }

    if($allSuccess){
        \DB::table('roles')->where('id',$role->id)->update([
            "send_api" => 1,
            "send_api_project_id" => implode(",", $newProjects)
        ]);

        return response()->json(['status'=>true,'message'=>"Role data sent successfully"]);
    }

    return response()->json(['status'=>false,'message'=>"Some APIs failed"]);
}


    public function save($req)
    {
        try {
               $data = [
                'role' => $req->input('role'),
                'short_description' => $req->input('short_description'),
            ];
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
                'short_description' => $req->input('short_description'),
                'is_active' => $req->is_active
            ];

            return $this->repo->update($data, $id);
        } catch (Exception $e) {
            Log::error("RoleService update error: " . $e->getMessage());
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
    //         Log::error("RoleService delete error: " . $e->getMessage());
    //         return false;
    //     }
    // }

    public function delete($req)
    {
        try {
            $id = base64_decode($req->id);

            // Get role name
            $role = $this->repo->edit($id); // assuming edit() returns role details
            $roleName = $role->role ?? 'This role';

            // Check if any employee has this role assigned
            $employeeCount = \DB::table('employees')
                ->where('role_id', $id)
                ->where('is_deleted', 0)
                ->count();

            if ($employeeCount > 0) {
                // Role is assigned to employees, cannot delete
                throw new Exception("Cannot Delete The Role '{$roleName}' Because It Is Assigned To One Or More Employees.");
            }

            // Soft delete role
            $data = ['is_deleted' => 1];
            return $this->repo->delete($data, $id);

        } catch (Exception $e) {
            \Log::error("RoleService delete error: " . $e->getMessage());
            throw $e; // re-throw so controller can catch and show message
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

//     public function updateStatus($req)
// {
//     try {
//         $id = base64_decode($req->id);
//         $isActive = $req->is_active; // 1 = activate, 0 = deactivate

//         // If trying to deactivate the role
//         if ($isActive == 0) {
//             // Fetch role name
//             $role = $this->repo->edit($id);
//             $roleName = $role->role ?? 'This role';

//             // Check if employees are using this role
//             $employeeCount = \DB::table('employees')
//                 ->where('role_id', $id)
//                 ->where('is_deleted', 0)
//                 ->count();

//             if ($employeeCount > 0) {
//                 throw new Exception("Cannot deactivate the role '{$roleName}' because it is assigned to one or more employees.");
//             }
//         }

//         // If allowed, update status
//         $data = ['is_active' => $isActive];
//         return $this->repo->updateStatus($data, $id);

//     } catch (Exception $e) {
//         \Log::error("RoleService updateStatus error: " . $e->getMessage());
//         throw $e; // Let the controller handle the message
//     }
// }


    public function find($id)
{
    try {
        return $this->repo->edit($id); // reuse existing edit() to fetch role
    } catch (Exception $e) {
        \Log::error("RoleService find error: " . $e->getMessage());
        return null;
    }
}


}
